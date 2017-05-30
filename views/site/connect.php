<?php
$this->title = 'Trello Track Board';
?>
<div id="connectContent">
    <!-- ko with: mainContent -->
    <div class="block">
        <label>Boards:</label>
        <div data-bind="foreach: boards" class="forBlock">
            <?php
                $bind = implode(' ,', [
                    'text: name',
                    'click: $parent.showLists.bind()',
                    'css: { selected: ($parent.boardId() == id)}'
                ])
            ?>
            <div class="name link" data-bind="<?= $bind ?>"></div>
        </div>
    </div>
    <div class="block" data-bind="visible: lists().length">
        <label>Lists:</label>
        <div data-bind="foreach: lists" class="forBlock">
            <?php
            $bind = implode(' ,', [
                'text: name',
                'click: $parent.showCards.bind($data)',
                'css: { selected: ($parent.listId() == id)}'
            ])
            ?>
            <div class="name link" data-bind="<?= $bind ?>"></div>
        </div>
    </div>
    <div class="block" data-bind="visible: cards().length">
        <label>Cards:</label>
        <div data-bind="foreach: cards" class="forBlock">
            <div class="name">
                <p data-bind="text: name"></p>
                <p>
                    <a href="#" class="link" data-bind="click: $parent.openTime.bind()">Add/change time?</a>
                    <span data-bind="visible: ($parent.cardId() == id)">
                        <?php
                            $bind = implode(' ,', [
                                'value: $parent.changedTime',
                            ]);
                        ?>
                        <input type="text" width="4" placeholder="Add time" data-bind="<?= $bind ?>" />
                        <input type="button" value="Save" data-bind="click: $parent.saveTime.bind($data, id)"/>
                    </span>
                </p>
            </div>
        </div>
    </div>
    <!-- /ko -->
</div>