@extends('main-layout.main')


  {{-- js section code --}}

    @section('css') 


    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css">

   <link rel="stylesheet" href="http://akquinet.github.io/jquery-toastmessage-plugin/demo/css/jquery.toastmessage-min.css">

	 <style type="text/css">

				td, th {
				    border: 1px solid #dddddd;
				    text-align: left;
				    padding: 8px;

				}

				.btn-info{margin: 2px;
				background-color: gray;}

        .classForInvoiceSent{background-color:#43a047!important;}
	  </style>

     @endsection

  {{-- js section code --}}

   @section('content')


  <button class="btn btn-primary" id="exportAttendance222">Export Excel</button>



      @if(session()->has('message'))
          <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <i class="fa fa-check-circle"></i> {{ session()->get('message') }}
          </div>

        @endif



          @if(session()->has('error'))    

             <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <i class="fa fa-check-circle"></i> {{ session()->get('error') }}
           </div>

           @endif


{{--     {{ $data->links() }}  --}}


 <table class="table table-hover" id="examples">
    <thead>
      <tr>
        <th>Customer id</th>
        <th>Company Name</th>
        <th>Email-id</th>
        <th>Phone Number</th>
        <th>Contact Name</th>
        <th>Invoice Sent Time</th>
      </tr>
    </thead>
    <tbody>

 @foreach($data as $dat)
      <tr>
        <td>{{$dat->id}}</td>
        <td>{{$dat->company_name}}</td>
        <td>{{$dat->email}}</td>
        <td>{{$dat->ext}} {{$dat->phone}} - {{$dat->phone_group}}</td>
        <td>{{$dat->contact_name}}</td>
        <td>{{date('d-m-Y  h:i A', strtotime($dat->invoice_sent_time))}}</td>
     </tr>
  @endforeach
    
  </tbody>
</table>

@endsection

@section('js')
<script type="text/javascript" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>

<script src="http://akquinet.github.io/jquery-toastmessage-plugin/demo/jquery.toastmessage-min.js"></script>

<script type="text/javascript" src="{!! asset('/js/Contents/tableExport.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/js/Contents/jquery.base64.js') !!}"></script>


<script type="text/javascript">

   function showToastMessage(msg){

		  var yourText = 'Success Dialog which is NOT sticky',
		        toastMessageSettings = {
		            text: msg.message,//yourText,
		            sticky: false,
		            position: 'top-right',
		            type: msg.success,//'success',
		            closeText: '',
		            close: function() {
		                console.log("toast is closed ...");
		            }
		        };

		       var myToast = $().toastmessage('showToast', toastMessageSettings);
		}


	$(document).ready(function(){
        $('.editableDataClass').blur(function(e){
        	e.preventDefault();
            //alert($(this).data('edit'));   

            var rowId = $(this).data('edit');

            console.log($(this));

            var tableName = $(this).data('table');

            var baseUrlMain = $('#allTheBaseUrl999987').val();

            var goUrl = baseUrlMain+'/edit_job/'+rowId;

            var textData  = $(this).text();


            console.log(goUrl);

            $.ajax({
            	url:goUrl,
            	type:'POST',
            	data:{_token:$('meta[name="csrf-token"]').attr('content'),"table_name":tableName,"data":textData,"row_id":rowId},
            	dataType:'json',
            	success:function(data){
            		console.log(data);

            		    var mesg = {message:data.message,status:data.success};
                        showToastMessage(mesg);

            	},
            	error:function(data){
            		console.log(data);
            	}
            });


        });  	 	
  	 });


</script>


  <script type="text/javascript">
  	 

  	 $(document).ready(function () {
        var table = $('#examples').DataTable();
        $('a.toggle-vis').on('click', function (e) {
            e.preventDefault();

            var column = table.column($(this).attr('data-column'));
            column.visible(!column.visible());
        });

        $('#examples tfoot th').each(function () {
            var title = $('#examples thead th').eq($(this).index()).text();
            $(this).html('<input tyepe="text" placeholder="Search ' + title + '"/>');
        });
        table.columns().eq(0).each(function (colIdx) {
            $('input', table.column(colIdx).footer()).on('keyup change', function () {
                table
                        .column(colIdx)
                        .search(this.value)
                        .draw();
            });
        });

        $('#exportAttendance222').bind('click', function (e) {

            $('#examples').tableExport({ type: 'excel', escape: 'false', bootstrap: true});
        });
    });
  </script>
@endsection
