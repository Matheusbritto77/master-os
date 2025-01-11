@extends('layouts.bar')

@section('content')
<div class="app-content p-4">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-4 text-white">Produtos com Estoque Baixo</h1>

        <div class="bg-white rounded-lg shadow">
            <table class="table-auto w-full border border-gray-300">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2 border-b">Nome do Produto</th>
                        <th class="px-4 py-2 border-b">Quantidade</th>
                        <th class="px-4 py-2 border-b">Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produtosBaixoEstoque as $produto)
                    <tr>
                        <td class="px-4 py-2 border-b">{{ $produto->nome_produto }}</td>
                        <td class="px-4 py-2 border-b">{{ $produto->quantidade }}</td>
                        <td class="px-4 py-2 border-b">{{ $produto->descricao }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-center text-gray-500">Não há produtos com estoque baixo.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
