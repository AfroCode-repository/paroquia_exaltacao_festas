{{--script em Js, para visualziar melhor basta apenas colocar a expressão entre as taks <script>...</script> --}}
@if (!isset($moreTable))
let table = $('{{$selectorTable}}').DataTable();
@else
table = $('{{$selectorTable}}').DataTable();
@endif
table.destroy();

$('{{$selectorTable}}').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    "order": [],
        "language":{
            "url": `{{env('APP_URL')}}/js/plugins/datatables/dataTables.Portuguese-Brasil.json`
        },
    responsive: true,
})
