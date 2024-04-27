<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use JsonException;

class articlesController extends Controller
{
    private function getArticlesData(): array | null
    {
        $articlesPath = storage_path('../app/data/dataArticles.json');

        if (!file_exists($articlesPath)) {
            abort(404, 'Error: Archivo de datos no encontrado.'); // Use abort for HTTP errors
        }

        try {
            $articlesContent = file_get_contents($articlesPath);
            $articles = json_decode($articlesContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new JsonException('Error al decodificar el archivo JSON.');
            }

            return $articles;
        } catch (JsonException $e) {
            abort(500, 'Error: Error al decodificar el archivo JSON: ' . $e->getMessage()); // Include informative error message
        }

        return null; // Indicate unsuccessful data retrieval (optional)
    }

    public function getArticles()
    {
        $articles = $this->getArticlesData();

        $data = [
            'status' => 200,
            'articles' => $articles
        ];
        return response()->json($data, 200);
    }
}
