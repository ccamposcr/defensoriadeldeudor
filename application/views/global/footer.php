            <?php $CI =& get_instance(); ?>
            <script type="text/javascript">
                const base_url = '<?php echo base_url();?>';
                let csrf_name = '<?php echo $CI->security->get_csrf_token_name(); ?>';
                let csrf_hash = '<?php echo $CI->security->get_csrf_hash(); ?>';
            </script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
            <script type="text/javascript" src="<?php echo base_url();?>public/dist/build.js"></script>
        </div>
    </body>
</html>