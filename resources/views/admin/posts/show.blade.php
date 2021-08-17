@extends('admin.layouts.app')

@section('title', 'Detalhes do Post')

@section('content')
    <a href="{{ route('posts.index') }}">Voltar</a><hr>

    <h1>Detalhes do Post {{ $post->title }}</h1>

    <ul>
        <li><strong>Título:</strong> {{ $post->title }}</li>
        <li><strong>Conteúdo:</strong> {{ $post->content }}</li>
        <li><strong>Criado em:</strong> {{ $post->created_at }}</li>
        <li><strong>Alterado em:</strong> {{ $post->updated_at }}</li>
    </ul>

    <form action="{{ route('posts.destroy', $post->id) }}" method="post">
        @csrf
        <input type="hidden" name="_method" value="delete">
        <button type="submit">Deletar o Post {{ $post->title }}</button>
    </form>    
@endsection