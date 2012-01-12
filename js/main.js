var $result_table, $explorer_tables, $$explorer_tables_list, $frm_sql, $sql, $tables_filter;
			
var sqlserver = {
    do_request: function(data, func){
        $.ajax({
            type: 'post',
            async: false,
            data: data,
            success: function(data){
                func(data);
            }
        });
    }
}
			
function resize_window() {
    $('#container').height($(window).height());
				
    $('#main').height($(window).height() - $('#frm-sql').outerHeight());
				
    $('#explorer-tables').height($('#explorer').outerHeight() - $('#explorer-filter').outerHeight() - 40);
				
    $('#result').width($('#main').outerWidth() - $('#explorer').outerWidth() - 20);
				
    $('#result-table').height($('#result').height() - 20);
    $('#result-table').width($('#result').width() - 20);
}
			
function filter_tables(){
				
    var str = $tables_filter.val();
				
    if (str.length === 0) {
        $("#explorer-tables a").show();
        return;
    }
                
    var $ele;
    $$explorer_tables_list.each(function(){
        $ele = $(this);
                    
        if ($ele.attr('rel').search(str.toLowerCase()) > -1) {
            $ele.show();
        } else {
            $ele.hide();
        }
    });
}
			
$(function(){
				
    $result_table = $('#result-table');
    $explorer_tables = $('#explorer-tables');
    $frm_sql = $('#frm-sql');
    $sql = $('#sql');
    $tables_filter = $('#tables-filter');
    //$save_on_history = $('#save-on-history'); Auno no se usa el history.
				
				
    resize_window();
				
    $(window).resize(function(){
        resize_window();
    });
				
				
    $tables_filter.keyup(filter_tables);
			
				
    sqlserver.do_request({
        fnctodo: 2
    }, function(data){
        $explorer_tables.html(data);
					
        $$explorer_tables_list = $explorer_tables.find('a.table');
					
        $$explorer_tables_list.dblclick(function(){
            $sql.val('SELECT TOP 100 * FROM ' + $(this).text());
						
            $frm_sql.submit();
						
            $(this).addClass('selected')
            .siblings().removeClass('selected');
        });
    });
				
    $frm_sql.submit(function(){
					
        var sql_string = $sql.val();
        
        if (sql_string == '') {
            return false;
        }
					
                    
        sqlserver.do_request({
            fnctodo: 1,
            sql: sql_string
        }, function(data){
            $result_table.html(data);
        });
        
					
        return false;
    });
});