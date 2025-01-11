<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    @page {
      size: A4;
      margin: 5mm;
    }

    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      line-height: 1.6;
      margin: 0;
      padding: 0;
    }

    .container {
      display: flex;
      flex-direction: column;
      width: 100%;
      height: 100vh;
    }

    .form-container {
      width: 100%;
      display: flex;
      flex-direction: column;
      margin: 0;
    }

    .form {
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 5px;
      box-sizing: border-box;
      margin-bottom: 0;
      width: calc(100% - 6mm);
    }

    .header {
      display: flex;
      justify-content: center;
      align-items: center;
      border-bottom: 1px solid #ddd;
      padding: 5px 0;
    }

    .logo-container {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      position: relative;
      top: 20px;
      left: 20px;
    }

    .logo-container img {
      max-width: 250px;
      max-height: 60px;
    }

    .title {
      text-align: center;
      font-size: 30px;
      font-weight: 700;
      color: #333;
      margin: 0;
      padding: 0;
      position: relative;
      top: -50px;
      left: -10px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .section {
      margin: 1px 0;
      padding: 5px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .section-title {
      font-size: 11px;
      font-weight: bold;
      margin: 0;
      border-bottom: 1px solid #ddd;
      padding: 2px 0;
    }

    .client-table {
      width: 100%;
      border-collapse: collapse;
    }

    .client-table th, .client-table td {
      border: 1px solid #ddd;
      padding: 3px;
      text-align: left;
      font-size: 10px;
    }

    .client-table th {
      background-color: #f9f9f9;
    }

    .signature-block {
      margin: 10px 0;
      text-align: center;
    }

    .signature-line {
      border-bottom: 1px solid #ddd;
      width: 150px;
      margin: 0 auto;
      padding: 5px;
    }

    .footer {
      border-top: 1px solid #ddd;
      padding: 5px 0;
      text-align: center;
    }

    .footer p {
      margin: 2px 0;
    }

    .terms {
      margin: 1px 0;
      padding: 1px;
      border: 1px solid #ddd;
      border-radius: 1px;
    }

    .terms h2 {
      font-size: 11px;
      margin: 0;
      padding-bottom: 2px;
      border-bottom: 1px solid #ddd;
    }

    .checklist {
      margin: 10px 0;
      padding: 5px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .checklist h2 {
      font-size: 11px;
      margin: 0;
      padding-bottom: 2px;
      border-bottom: 1px solid #ddd;
    }

    .checklist-container {
      display: flex;
      justify-content: space-between;
    }

    .checklist-column {
      list-style: none;
      padding: 0;
      margin: 0;
      width: 48%;
    }

    .checklist-column li {
      font-size: 10px;
      margin: 5px 0;
    }

    .checklist input[type="checkbox"] {
      margin-right: 5px;
    }

    .checklist-container-left {
      display: flex;
      flex-direction: column;
      width: 48%;
    }

    .checklist-container-right {
      display: flex;
      flex-direction: column;
      width: 48%;
      
    }

    .checklist-container-wrapper {
      display: flex;
      justify-content: space-between;
    }

    @media print {
      .container {
        page-break-inside: avoid;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <div class="container">
    <div class="form-container">
      <div class="form">
        <div class="header">
          <div class="logo-container">
            <img src="{{ $imageBase64 }}" alt="Logo Cliente" class="client-logo">
            <div class="title">{{ $empresa->nome }}</div>
          </div>
        </div>
        <div class="section">
          <h2 class="section-title"></h2>
          <table class="client-table">
            <tbody>
              <tr>
                <th>Endereço:</th>
                <td>{{ $empresa->rua }},{{ $empresa->bairro }},{{ $empresa->cidade }} ,{{ $empresa->estado }}</td>
              </tr>
              <tr>
                <th>Telefone:</th>
                <td>{{ $empresa->telefone }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="section">
          <h2 class="section-title">DADOS DO CLIENTE</h2>
          <table class="client-table">
            <tbody>
              <tr>
                <th>Nome:</th>
                <td>{{ $ordem->cliente }}</td>
              </tr>
              <tr>
                <th>Endereço:</th>
                <td>{{ $ordem->rua }}, {{ $ordem->bairro }}</td>
              </tr>
              <tr>
                <th>CEP:</th>
                <td>{{ $ordem->cep }}</td>
              </tr>
              <tr>
                <th>Cidade:</th>
                <td>{{ $ordem->cidade }}</td>
              </tr>
              <tr>
                <th>Estado:</th>
                <td>{{ $ordem->state }}</td>
              </tr>
              <tr>
                <th>Telefone:</th>
                <td>{{ $ordem->phone_number }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="section">
          <h2 class="section-title">EQUIPAMENTO</h2>
          <div>
            <strong>Modelo:</strong> {{ $ordem->modelo }}
          </div>
          <div>
            <strong>Serviço a ser realizado:</strong> {{ $ordem->problema }}
          </div>
        </div>
        <div class="section">
          <h2 class="section-title">OBSERVAÇÕES</h2>
          <p>{{ $ordem->observacoes }}</p>
        </div>
        

        <div class="section terms">
          <h2 class="section-title">TERMO DE SERVIÇO</h2>
          <p>Ao deixar o celular para assistência técnica, o cliente concorda com os seguintes termos:</p>
          <ul>
            <li>Os dados fornecidos são verdadeiros e precisos.</li>
            <li>A assistência técnica não se responsabiliza por danos não causados por nossos serviços.</li>
            <li>Qualquer serviço adicional será comunicado ao cliente antes da execução.</li>
            <li>O prazo para o conserto será informado e pode variar dependendo do problema.</li>
            <li>A garantia do serviço realizado é de acordo com a política da empresa.</li>
          </ul>
        </div>

        <div class="section checklist">
          <h2 class="section-title">CHECKLIST DO CELULAR</h2>
          <div class="checklist-container-wrapper">
            <div class="checklist-container-left">
              <ul class="checklist-column">
                <li><input type="checkbox" disabled> Tela quebrada</li>
                <li><input type="checkbox" disabled> Botões funcionando</li>
                <li><input type="checkbox" disabled> Conector de carregamento</li>
                <li><input type="checkbox" disabled> Molhado</li>
                <li><input type="checkbox" disabled> Aparelho liga ?</li>
              </ul>
            </div>
            <div class="checklist-container-right">
              <ul class="checklist-column">
                <li><input type="checkbox" disabled> chip ficou ?</li>
              
                <li><input type="checkbox" disabled> campinha ?</li>
                <li><input type="checkbox" disabled> Bateria estufada ?</li>
                <li><input type="checkbox" disabled> Câmera funcionando</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="section signature-block">
          <div class="signature-line"></div>
          <p>Assinatura do Cliente</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

<body>
  <div class="container">
    <div class="form-container">
      <div class="form">
        <div class="header">
          <div class="logo-container">
            <img src="{{ $imageBase64 }}" alt="Logo Cliente" class="client-logo">
            <div class="title">{{ $empresa->nome }}</div>
          </div>
        </div>
        <div class="section">
          <h2 class="section-title"></h2>
          <table class="client-table">
            <tbody>
              <tr>
                <th>Endereço:</th>
                <td>{{ $empresa->rua }},{{ $empresa->bairro }},{{ $empresa->cidade }} ,{{ $empresa->estado }}</td>
              </tr>
              <tr>
                <th>Telefone:</th>
                <td>{{ $empresa->telefone }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="section">
          <h2 class="section-title">DADOS DO CLIENTE</h2>
          <table class="client-table">
            <tbody>
              <tr>
                <th>Nome:</th>
                <td>{{ $ordem->cliente }}</td>
              </tr>
              <tr>
                <th>Endereço:</th>
                <td>{{ $ordem->rua }}, {{ $ordem->bairro }}</td>
              </tr>
              <tr>
                <th>CEP:</th>
                <td>{{ $ordem->cep }}</td>
              </tr>
              <tr>
                <th>Cidade:</th>
                <td>{{ $ordem->cidade }}</td>
              </tr>
              <tr>
                <th>Estado:</th>
                <td>{{ $ordem->state }}</td>
              </tr>
              <tr>
                <th>Telefone:</th>
                <td>{{ $ordem->phone_number }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="section">
          <h2 class="section-title">EQUIPAMENTO</h2>
          <div>
            <strong>Modelo:</strong> {{ $ordem->modelo }}
          </div>
          <div>
            <strong>Serviço a ser realizado:</strong> {{ $ordem->problema }}
          </div>
        </div>
        <div class="section">
          <h2 class="section-title">OBSERVAÇÕES</h2>
          <p>{{ $ordem->observacoes }}</p>
        </div>

        <div class="section terms">
          <h2 class="section-title">TERMO DE SERVIÇO</h2>
          <p>Ao deixar o celular para assistência técnica, o cliente concorda com os seguintes termos:</p>
          <ul>
            <li>Os dados fornecidos são verdadeiros e precisos.</li>
            <li>A assistência técnica não se responsabiliza por danos não causados por nossos serviços.</li>
            <li>Qualquer serviço adicional será comunicado ao cliente antes da execução.</li>
            <li>O prazo para o conserto será informado e pode variar dependendo do problema.</li>
            <li>A garantia do serviço realizado é de acordo com a política da empresa.</li>
          </ul>
        </div>

        <div class="section checklist">
          <h2 class="section-title">CHECKLIST DO CELULAR</h2>
          <div class="checklist-container-wrapper">
            <div class="checklist-container-left">
              <ul class="checklist-column">
                <li><input type="checkbox" disabled> Tela quebrada</li>
                <li><input type="checkbox" disabled> Botões funcionando</li>
                <li><input type="checkbox" disabled> Conector de carregamento</li>
                <li><input type="checkbox" disabled> Molhado</li>
                <li><input type="checkbox" disabled> Aparelho liga ?</li>
              </ul>
            </div>
            <div class="checklist-container-right">
              <ul class="checklist-column">
                <li><input type="checkbox" disabled> chip ficou ?</li>
              
                <li><input type="checkbox" disabled> campinha ?</li>
                <li><input type="checkbox" disabled> Bateria estufada ?</li>
                <li><input type="checkbox" disabled> Câmera funcionando</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="section signature-block">
          <div class="signature-line"></div>
          <p>Assinatura do Cliente</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
