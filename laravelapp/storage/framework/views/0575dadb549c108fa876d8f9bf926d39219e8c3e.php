<?php $__env->startSection('content'); ?>
<div class="row">
   <div class="center-block" >
      <?php echo QrCode::size(100)->generate($url);; ?>

   </div>
</div>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>