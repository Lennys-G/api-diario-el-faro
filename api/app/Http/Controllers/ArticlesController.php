<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class ArticlesController extends Controller
{
    //______________________ Auxiliar para obtener todos los artículos______________________
    private function loadArticlesData()
    {
        $articlesPath = storage_path('app/data/dataArticles.json');

        if (!file_exists($articlesPath)) {
            return response()->json('Error: Data file not found.', 404);
        }

        $articles = json_decode(file_get_contents($articlesPath), true);

        if (!$articles) {
            return response()->json(
                'Error: No se puede decodificar los datos JSON.',
                500
            );
        }

        return $articles;
    }


    //______________________Añadir un artículo nuevo______________________
    public function addNewArticle(Request $request)
    {
        $newItem = $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'url' => 'required|string',
            'urlToImage' => 'required|string',
        ]);

        $articles = $this->loadArticlesData();
        $uuid = Uuid::uuid4();
        $newItem['id'] = $uuid->toString();

        $articles[] = $newItem;

        $jsonData = json_encode($articles, JSON_PRETTY_PRINT);
        file_put_contents('app/data/dataArticles.json', $jsonData);

        return response()->json(['message' => 'Articulo creado exitosamente', 'data' => $newItem], 201);
    }

    //______________________Obtener artículos por categoría______________________
    public function getArticlesByCategory($category)
    {
        $articles = $this->loadArticlesData();

        $filteredArticles = array_filter($articles, function ($article) use ($category) {
            return $article['category'] === $category;
        });

        return response()->json($filteredArticles);
    }
}
