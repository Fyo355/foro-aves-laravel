<?php

use App\Models\Post;
use Inertia\Testing\AssertableInertia as Assert;

test('guests can see the posts list', function () {
    // Preparar: crea 3 posts con la factory
    Post::factory()->count(3)->create();
    // Actuar: GET /posts sin actingAs (es un visitante)
    $response = $this->get('/posts');
    // Comprobar: respuesta OK, componente 'Posts/Index' y 3 posts
    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Posts/Index')
            ->has('posts', 3)
        );
});

test('posts list only exposes the author id and name', function () {
    // Preparar: 1 post
    $post = Post::factory()->create();
    // Actuar: GET /posts
    $response = $this->get('/posts');
    // Comprobar: entra en 'posts.0.user' y exige solo id y name
    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Posts/Index')
            ->has('posts.0.user', fn (Assert $page) => $page
                ->where('id', $post->user->id)
                ->where('name', $post->user->name)
            )
        );
});
