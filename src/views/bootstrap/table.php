<form action="<?php echo $table->getFormAction() ?>" method="post">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="col-md-4">
                <?php echo $table->getTitle(); ?>
            </div>

            <?php if ($table->hasActions('table')): ?>
                <div class="col-md-8 text-right">
                    <?php foreach ($table->actions() as $action): ?>
                        <?php echo $action->render() ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="clearfix"></div>
        </div>
        
        <table class="table">
            <thead>
                <tr>
                    <?php foreach ($table->columns() as $column): ?>
                        <th <?php foreach ($column->getAttributes() as $attr => $val): ?>
                            <?php echo $attr; ?>="<?php echo $val; ?>"
                        <?php endforeach; ?>>

                            <?php if ($column->isSortable() && $column->getHref()): ?>
                                <a href="<?php echo $column->getHref(); ?>">
                                    <?php echo $column->getLabel(); ?>
                                </a>
                                <?php if (isset($column->sort)) { ?>
                                    <span class="glyphicon glyphicon-chevron-<?= $column->sort ?>"></span>
                                <?php } ?>
                            <?php else: ?>
                                <?php echo $column->getLabel(); ?>
                            <?php endif; ?>

                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>

            <tbody>
                <?php if($table->hasRows()): ?>
                    <?php foreach ($table->getRows() as $row): ?>
                        <tr data-id="<?php echo $row->getId(); ?>">
                            <?php foreach ($row->cells() as $cell): ?>
                                <td><?php echo $cell->value; ?></td>
                            <?php endforeach; ?>
                            <?php if ($row->hasActions()): ?>
                                <td>
                                    <?php foreach ($row->actions() as $action): ?>
                                        <?= $action->render() ?>
                                    <?php endforeach; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?echo count($table->getColumns()) ?>">No Results Found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($table->hasLinks()): ?>
            <div class="panel-footer">
                <?php echo $table->getLinks() ?>
            </div>
        <?php endif; ?>
    </div>
</form>
