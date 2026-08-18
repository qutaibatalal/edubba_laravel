<?php $__env->startSection('title', __('transport.index.title')); ?>
<?php $__env->startSection('page', __('transport.index.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('transport.index.title'); ?></h1>
        <p><?php echo app('translator')->get('transport.index.subtitle'); ?></p>
    </div>
    <div class="row mx-0">
        <div class="col-6">
            <a href="<?php echo e(route('admin.transport.create_vehicle')); ?>" class="btn btn-primary w-100 mb-2">
                <i class="bi bi-bus me-1"></i> <?php echo app('translator')->get('transport.index.add_vehicle'); ?>
            </a>
        </div>
        <div class="col-6">
            <a href="javascript:void(0)" class="btn btn-outline-primary w-100 mb-2" onclick="showRouteForm()">
                <i class="bi bi-road me-1"></i> <?php echo app('translator')->get('transport.index.add_route'); ?>
            </a>
        </div>
    </div>
</div>
</div>

<div class="card hoverable mt-3">
    <div class="card-body">
        <h4 class="mb-3 fw-bold"><?php echo app('translator')->get('transport.index.vehicles_title'); ?></h4>
        <div class="table-responsive">
            <table class="table table-edb mb-0 align-middle">
                <thead>
                    <tr>
                        <th><?php echo app('translator')->get('transport.vehicle.plate_number'); ?></th>
                        <th><?php echo app('translator')->get('transport.vehicle.model'); ?></th>
                        <th><?php echo app('translator')->get('transport.vehicle.capacity'); ?></th>
                        <th><?php echo app('translator')->get('transport.vehicle.driver'); ?></th>
                        <th><?php echo app('translator')->get('transport.index.phone'); ?></th>
                        <th class="text-end"><?php echo app('translator')->get('transport.status'); ?></th>
                        <th class="text-end"><?php echo app('translator')->get('transport.actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($v->plate_number); ?></td>
                        <td><?php echo e($v->model ?? '—'); ?></td>
                        <td><?php echo e($v->capacity); ?></td>
                        <td><?php echo e($v->driver_name ?? '—'); ?></td>
                        <td><?php echo e($v->driver_phone ?? '—'); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($v->active ? 'success' : 'danger'); ?>-soft">
                                <?php echo e($v->active ? __('transport.active') : __('transport.inactive')); ?>

                            </span>
                        </td>
                        <td class="text-end">
                            <a href="javascript:void(0)" class="btn btn-link btn-sm"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card hoverable mt-3">
    <div class="card-body">
        <h4 class="mb-3 fw-bold"><?php echo app('translator')->get('transport.index.routes_title'); ?></h4>
        <div class="table-responsive">
            <table class="table table-edb mb-0 align-middle">
                <thead>
                    <tr>
                        <th><?php echo app('translator')->get('transport.route.name'); ?></th>
                        <th><?php echo app('translator')->get('transport.route.vehicle'); ?></th>
                        <th><?php echo app('translator')->get('transport.route.description'); ?></th>
                        <th class="text-end"><?php echo app('translator')->get('transport.status'); ?></th>
                        <th class="text-end"><?php echo app('translator')->get('transport.actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $routes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($r->name); ?></td>
                        <td><?php echo e($r->vehicle?->plate_number ?? '—'); ?></td>
                        <td><?php echo e($r->description ?? '—'); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($r->active ? 'success' : 'danger'); ?>-soft">
                                <?php echo e($r->active ? __('transport.active') : __('transport.inactive')); ?>

                            </span>
                        </td>
                        <td class="text-end">
                            <a href="javascript:void(0)" class="btn btn-link btn-sm"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function showRouteForm() {
    // Simple alert for now - can be expanded with a modal
    alert('<?php echo e(__('transport.index.route_soon')); ?>');
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/transport/index.blade.php ENDPATH**/ ?>