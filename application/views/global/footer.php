<?php $CI =& get_instance(); ?>
<script type="text/javascript">
    var base_url = '<?php echo base_url();?>';
    var csrf_name = '<?php echo $CI->security->get_csrf_token_name(); ?>';
    var csrf_hash = '<?php echo $CI->security->get_csrf_hash(); ?>';
</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/main.js"></script>
        </div>
    </body>
</html>