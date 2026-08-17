<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileStreamController extends Controller
{
    /**
     * Securely serve/stream files from storage/app/public without symlink dependency.
     */
    public function show(Request $request, string $path): BinaryFileResponse
    {
        // 1. Sanitize input path
        $cleanPath = ltrim(urldecode($path), '/\\');

        // 2. Reject path traversal attempts
        if (str_contains($cleanPath, '..') || str_contains($cleanPath, "\0")) {
            abort(403, 'Akses ke file ini tidak diizinkan.');
        }

        // 3. Verify file exists in public disk
        if (!Storage::disk('public')->exists($cleanPath)) {
            abort(404, 'File tidak ditemukan.');
        }

        $filePath = Storage::disk('public')->path($cleanPath);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        // 4. Verify resolved realpath is strictly inside storage/app/public (or active disk root)
        $diskRoot = Storage::disk('public')->path('');
        $baseDir = realpath($diskRoot) ?: realpath(storage_path('app/public'));
        $realPath = realpath($filePath);

        if (!$baseDir || !$realPath) {
            abort(404, 'File tidak ditemukan.');
        }

        // Normalize slashes for cross-platform safety (Windows & Linux)
        $normalizedBaseDir = rtrim(str_replace('\\', '/', $baseDir), '/');
        $normalizedRealPath = str_replace('\\', '/', $realPath);

        if (!str_starts_with($normalizedRealPath, $normalizedBaseDir . '/') && $normalizedRealPath !== $normalizedBaseDir) {
            abort(403, 'Akses ke lokasi file di luar storage publik ditolak.');
        }

        // 5. Serve file securely with inline disposition and caching headers
        $mimeType = mime_content_type($realPath) ?: 'application/octet-stream';
        $filename = basename($realPath);

        return response()->file($realPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
