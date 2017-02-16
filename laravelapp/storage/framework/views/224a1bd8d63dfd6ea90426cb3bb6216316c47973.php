<?php $__env->startSection('content'); ?>
<div class="container">
    <?php if($users): ?>
     <table class="table table-bordered">
     <tr>
         <th>用户名</th>
         <th>手机号</th>
         <th>添加时间</th>
     </tr>
     <?php foreach($users as $user): ?>
     <tr>
     	<td><?php echo e($user->user_id); ?></td>
     	<td><?php echo e($user->user_open_id); ?></td>
     	<td><?php echo e($user->created_at); ?></td>

     </tr>
     <?php endforeach; ?>
    </table>
    <?php echo $users->render(); ?>

   <?php else: ?>
       <table class="table table-bordered">
        <tr><p>no bady here</p></tr>
       </table>
   <?php endif; ?>
</div>

    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>