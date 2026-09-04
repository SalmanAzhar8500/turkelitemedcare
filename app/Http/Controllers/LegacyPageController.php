<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LegacyPageController extends Controller
{
    public function show(Request $request)
    {
        $path = trim($request->path(), '/');
        $candidates = $this->candidatesFor($path);

        foreach ($candidates as $candidate) {
            $fullPath = base_path($candidate);

            if (! File::exists($fullPath) || ! File::isFile($fullPath)) {
                continue;
            }

            return response()->file($fullPath, [
                'Content-Type' => $this->contentTypeFor($fullPath),
            ]);
        }

        abort(404);
    }

    /**
     * @return array<int, string>
     */
    private function candidatesFor(string $path): array
    {
        if ($path === '') {
            return ['index.html'];
        }

        $candidates = [$path];

        if (! str_contains($path, '.')) {
            $candidates[] = $path.'.html';
            $candidates[] = $path.'/index.html';
        }

        if (str_ends_with($path, '/')) {
            $trimmed = rtrim($path, '/');

            if ($trimmed !== '') {
                $candidates[] = $trimmed.'/index.html';
            }
        }

        return array_values(array_unique($candidates));
    }

    private function contentTypeFor(string $path): string
    {
        return match (pathinfo($path, PATHINFO_EXTENSION)) {
            'html' => 'text/html; charset=UTF-8',
            'xml' => 'application/xml; charset=UTF-8',
            'txt' => 'text/plain; charset=UTF-8',
            'webmanifest' => 'application/manifest+json',
            default => 'application/octet-stream',
        };
    }
}
