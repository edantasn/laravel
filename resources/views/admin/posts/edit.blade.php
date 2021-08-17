@extends('admin.layouts.app')

@section('title', 'Editar o Post')

@section('content')
    <a href="{{ route('posts.index') }}">Voltar</a><hr>

    <h1>Editar o Post <strong>{{ $post->title }}</strong></h1>

    <form action="{{ route('posts.update', $post->id) }}" method="post" enctype="multipart/form-data">
        @method('put')
        @include('admin.posts._partials.form')
    </form>
@endsection