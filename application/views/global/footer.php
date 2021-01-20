<?php $CI =& get_instance(); ?>
<script>
    var base_url = '<?php echo base_url();?>';
    var csrf_name = '<?php echo $CI->security->get_csrf_token_name(); ?>';
    var csrf_hash = '<?php echo $CI->security->get_csrf_hash(); ?>';
</script>
        </div>
    </body>
</html>