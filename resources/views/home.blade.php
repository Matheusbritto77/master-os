@extends('layouts.bar')

@section('content')
<style>
.ag-format-container {
  width: 100%; /* Ajustado para ocupar toda a largura disponível */
  max-width: 1142px; /* Largura máxima para manter a responsividade */
  margin: 0 auto;
  padding: 0 15px; /* Espaçamento horizontal */
}

body {
  background-color: #000;
}

.ag-courses_box {
  display: flex;
  flex-wrap: wrap;
  gap: 18px; /* Espaçamento entre os cartões */
  padding: 30px 0; /* Espaçamento vertical */
}

.ag-courses_item {
  flex: 1 1 calc(20% - 18px); /* Ajusta o tamanho do card e o espaçamento */
  max-width: calc(20% - 18px); /* Limita a largura máxima do card */
  overflow: hidden;
  border-radius: 17px; /* Bordas arredondadas */
}

.ag-courses-item_link {
  display: block;
  padding: 18px 12px; /* Espaçamento interno */
  background-color: #171e29;
  overflow: hidden;
  position: relative;
}

.ag-courses-item_link:hover,
.ag-courses-item_link:hover .ag-courses-item_date {
  text-decoration: none;
  color: #FFF;
}

.ag-courses-item_link:hover .ag-courses-item_bg {
  transform: scale(6); /* Efeito de aumento */
}

.ag-courses-item_title {
  min-height: 52px; /* Altura mínima do título */
  margin: 0 0 15px; /* Margem inferior */
  overflow: hidden;
  font-weight: bold;
  font-size: 18px; /* Tamanho da fonte do título */
  color: #FFF;
  z-index: 2;
  position: relative;
}

.ag-courses-item_date-box {
  font-size: 20px; /* Tamanho da fonte da data */
  color: #FFF;
  z-index: 2;
  position: relative;
}

.ag-courses-item_date {
  font-weight: bold;
  color: #f9b234;
  transition: color .5s ease;
}

.ag-courses-item_bg {
  height: 77px; /* Altura do fundo */
  width: 77px; /* Largura do fundo */
  background-color: #557199;
  z-index: 1;
  position: absolute;
  top: -45px; /* Posição do fundo */
  right: -45px; /* Posição do fundo */
  border-radius: 50%; /* Bordas arredondadas do fundo */
  transition: all .5s ease;
}

.ag-courses_item:nth-child(2n) .ag-courses-item_bg {
  background-color: #557199;
}

.ag-courses_item:nth-child(3n) .ag-courses-item_bg {
  background-color: #557199;
}

.ag-courses_item:nth-child(4n) .ag-courses-item_bg {
  background-color: #557199;
}

.ag-courses_item:nth-child(5n) .ag-courses-item_bg {
  background-color: #557199;
}

.ag-courses_item:nth-child(6n) .ag-courses-item_bg {
  background-color: #557199;
}

@media only screen and (max-width: 979px) {
  .ag-courses_item {
    flex: 1 1 calc(30% - 18px); /* Ajuste para telas menores */
    max-width: calc(30% - 18px); /* Limita a largura máxima */
  }

  .ag-courses-item_title {
    font-size: 14px; /* Tamanho menor do título */
  }
}

@media only screen and (max-width: 767px) {
  .ag-format-container {
    width: 100%; /* Largura total */
    padding: 0 10px; /* Ajuste do espaçamento horizontal */
  }
}

@media only screen and (max-width: 639px) {
  .ag-courses_item {
    flex: 1 1 100%; /* Largura total em telas pequenas */
    max-width: 100%; /* Largura máxima total */
  }

  .ag-courses-item_title {
    min-height: 43px; /* Altura mínima do título em telas pequenas */
    font-size: 14px; /* Tamanho da fonte do título em telas pequenas */
  }

  .ag-courses-item_link {
    padding: 13px 24px; /* Ajuste do espaçamento interno */
  }

  .ag-courses-item_date-box {
    font-size: 10px; /* Tamanho da fonte da data em telas pequenas */
  }
}
</style>

<div class="ag-format-container">
    <div class="ag-courses_box">
        <!-- Card: Total de Ordens -->
        <div class="ag-courses_item">
            <a href="#" class="ag-courses-item_link">
                <div class="ag-courses-item_bg"></div>
                <div class="ag-courses-item_title">Total de Ordens</div>
                <div class="ag-courses-item_date-box">
                    <span class="ag-courses-item_date">{{ $totalOrdens }}</span>
                </div>
            </a>
        </div>

        <!-- Card: Total de Clientes -->
        <div class="ag-courses_item">
            <a href="#" class="ag-courses-item_link">
                <div class="ag-courses-item_bg"></div>
                <div class="ag-courses-item_title">Total de Clientes</div>
                <div class="ag-courses-item_date-box">
                    <span class="ag-courses-item_date">{{ $totalClientes }}</span>
                </div>
            </a>
        </div>

        <!-- Card: Total de Funcionários -->
        <div class="ag-courses_item">
            <a href="#" class="ag-courses-item_link">
                <div class="ag-courses-item_bg"></div>
                <div class="ag-courses-item_title">Total de Funcionários</div>
                <div class="ag-courses-item_date-box">
                    <span class="ag-courses-item_date">{{ $totalFuncionarios }}</span>
                </div>
            </a>
        </div>

        <!-- Card: Total de Produtos Registrados -->
        <div class="ag-courses_item">
            <a href="#" class="ag-courses-item_link">
                <div class="ag-courses-item_bg"></div>
                <div class="ag-courses-item_title">Total de Produtos Registrados</div>
                <div class="ag-courses-item_date-box">
                    <span class="ag-courses-item_date">{{ $totalProdutosRegistrados }}</span>
                </div>
            </a>
        </div>

        <!-- Card: Total do Caixa Diário -->
        <div class="ag-courses_item">
            <a href="#" class="ag-courses-item_link">
                <div class="ag-courses-item_bg"></div>
                <div class="ag-courses-item_title">Total do Caixa Diário</div>
                <div class="ag-courses-item_date-box">
                    <span class="ag-courses-item_date">R$ {{ number_format($totalCaixaDiario, 2, ',', '.') }}</span>
                </div>
            </a>
        </div>

        <!-- Card: Status do Caixa -->
        <div class="ag-courses_item">
            <a href="#" class="ag-courses-item_link">
                <div class="ag-courses-item_bg"></div>
                <div class="ag-courses-item_title">Status do Caixa</div>
                <div class="ag-courses-item_date-box">
                    <span class="ag-courses-item_date" id="statusCaixaTexto">{{ $caixaStatus }}</span>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
