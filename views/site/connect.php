<?php
$this->title = 'Trello Track Board';
?>
<div id="connectContent">
    <!-- ko with: mainContent -->
    <div class="block">
        <label>Boards:</label>
        <div data-bind="foreach: boards" class="forBlock">
            <div class="name" data-bind="text: name, click: $parent.showLists.bind($data)"></div>
        </div>
    </div>
    <div class="block" data-bind="visible: lists().length">
        <label>Lists:</label>
        <div data-bind="foreach: lists" class="forBlock">
            <div class="name" data-bind="text: name, click: $parent.showCards.bind($data)"></div>
        </div>
    </div>
    <div class="block" data-bind="visible: cards().length">
        <label>Cards:</label>
        <div data-bind="foreach: cards" class="forBlock">
            <div class="name" data-bind="text: name"></div>
        </div>
    </div>
    <!-- /ko -->
</div>