<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>

	  <script type="text/javascript">
		$(document).ready(function() {
		  var unique_id = $.gritter.add({
			title: 'Não tem relatório',
			text: 'É necessário gerar o relatório antes!',
			image: 'dist/img/user01.png',
			sticky: false,
			time: 8000,
			class_name: 'my-sticky-class'
		  });
		  return false;
		});
	  </script>