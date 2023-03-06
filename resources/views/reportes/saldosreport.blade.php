<!DOCTYPE html>
<html>
<head>
   <title>Reporte de saldos</title>
<style>
   table.minimalistBlack {
    border: 0px solid #000000;
    width: 95%;
    text-align: left;
    border-collapse: collapse;
  }
  table.minimalistBlack td, table.minimalistBlack th {
    border: 1px solid #000000;
    padding: 5px 4px;
  }
  table.minimalistBlack tbody td {
    font-size: 12px;
  }
  table.minimalistBlack thead {
    background: #CFCFCF;
    background: -moz-linear-gradient(top, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
    background: -webkit-linear-gradient(top, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
    background: linear-gradient(to bottom, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
    border-bottom: 2px solid #000000;
  }
  table.minimalistBlack thead th {
    font-size: 14px;
    font-weight: bold;
    color: #000000;
    text-align: center;
  }
  table.minimalistBlack tfoot td {
    font-size: 12px;
  }

  .numbers {
    text-align: right;
  }
</style>
</head>
<body>



     <h1>{{ $title }}</h1>
    <p>Fecha: {{$date }}</p>  
    
   
    <p></p>
    
        <table class="minimalistBlack" >
            <thead >
                <tr> 
                    <th>Cod Fca</th>
                    <th>Nombre</th>
                    <th>Saldo</th>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($saldos as $cliente )
                @if ($cliente->total > 0)
                    <tr>
                        <td>  {{ $cliente->cod_fca }} </td>
                        <td>  {{ $cliente->nombre }} </td>
                        <td class="numbers">{{ $cliente->total }}</td>
                    <tr>
                @endif
                
                @endforeach
            </tbody>
        </table>
</body>
</html>