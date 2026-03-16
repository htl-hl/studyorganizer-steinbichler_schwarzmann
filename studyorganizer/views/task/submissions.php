<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Task $model */
/** @var array $taskUsers */
?>

<?php $this->title = 'Submissions: ' . $model->title; ?>
<?php $this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']]; ?>
<?php $this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]]; ?>
<?php $this->params['breadcrumbs'][] = 'Submissions'; ?>

<div class="task-submissions">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">📋 Submissions for: <?= Html::encode($model->title) ?></h1>
            <p class="text-muted mb-0">View all student submissions and download files</p>
        </div>
        <?= Html::a('← Back to Task', ['view', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php if (empty($taskUsers)): ?>
        <div class="alert alert-info d-flex align-items-center">
            <i class="bi bi-info-circle me-2"></i>
            <div>
                <h5 class="alert-heading">No submissions yet</h5>
                <p class="mb-0">No users have submitted this task or no users are assigned.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($taskUsers as $taskUser): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <h6 class="card-title mb-2"><?= Html::encode($taskUser['username']) ?></h6>

                            <!-- Status in eigener Zeile -->
                            <?php if ($taskUser['isCompleted']): ?>
                                <span class="badge bg-success mb-3 d-block"><i class="bi bi-check-circle me-1"></i>Submitted</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark mb-3 d-block"><i class="bi bi-clock me-1"></i>Pending</span>
                            <?php endif; ?>

                            <?php if ($taskUser['isCompleted'] && $taskUser['return_document']): ?>
                                <?= Html::a(
                                        '<i class="bi bi-download me-2"></i>Download Submission',
                                        ['download-return-doc', 'id' => $taskUser['taskId'], 'userId' => $taskUser['userId']],
                                        [
                                                'class' => 'btn btn-outline-primary w-100',
                                                'title' => 'Download ' . Html::encode($taskUser['username'] . '\'s submission')
                                        ]
                                ) ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-file-earmark-x text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted small mt-2 mb-0">No file submitted</p>
                                </div>
                            <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4 text-center">
            <small class="text-muted">
                Showing <?= count($taskUsers) ?> of <?= count($taskUsers) ?> assigned users
            </small>
        </div>
    <?php endif; ?>
</div>
