<div class="table-container">
	<form action="<?= $table->getFormAction() ?>" method="post">
		<div class="table-header">
			<div class="col-md-8">
				<h3><?= $table->getTitle() ?></h3>
			</div>
			<?php if ($table->hasActions('table')) { ?>
				<div class="table-actions col-sm-4">
					<?php foreach($table->getActions('table') as $action) { ?>
						<?= $action->render() ?>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<table class="table">
			<thead>
				<tr>
					<?php foreach ($table->getColumns() as $column) { ?>
						<th <?php foreach ($column->getAttributes() as $attr => $val) { ?>
							<?=$attr?>="<?=$val?>"
							<?php } ?>>
							<?php if ($column->isSortable() && $column->getHref()) { ?>
								<a href="<?=$column->getHref()?>">
									<?=$column->getLabel()?>
									<?php if (isset($column->sort)) { ?>
										 <span class="glyphicon glyphicon-chevron-<?= $column->sort ?>"></span>
									<?php } ?>
								</a>
							<?php } else { ?>
								<?=$column->getLabel()?>
							<?php } ?>
						</th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php if($table->hasRows()) { ?>
					<?php foreach ($table->getRows() as $row) { ?>
						<tr data-id="<?=$row->getId()?>">
							<?php foreach ($row->cells() as $cell) { ?>
								<td><?= $cell->value ?></td>
							<?php } ?>
                            <?php if ($row->hasActions()): ?>
                                <td>
                                    <?php foreach ($row->actions() as $action): ?>
                                        <?= $action->render() ?>
                                    <?php endforeach; ?>
                                </td>
                            <?php endif; ?>
						</tr>
					<?php } ?>
				<?php } else { ?>
					<tr>
						<td colspan="<?= count($table->getColumns()) ?>">No Results Found.</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</form>
	<div class="table-footer">
		<?php if ($table->hasLinks()) { ?>
			<?= $table->getLinks() ?>
		<?php } ?>
	</div>
</div>