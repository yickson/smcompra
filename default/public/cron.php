<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script>
$.ajax({
    type  : "post",
    cache : false,
    url   : <?php echo "'https://localhost/smcompra/cron'" ?>,
    success : function(result){
	console.log("OKKK");
    },
    error : function(){
	console.log("error");	
    }
});
</script>

