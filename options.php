<?php

defined('B_PROLOG_INCLUDED') or die();

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Drogalov\OrderHandler\Module;
use Drogalov\OrderHandler\Service\ModuleDefaults;
use Drogalov\OrderHandler\Service\UnpaidOrdersAgentHelper;

Loc::loadMessages(__FILE__);

$moduleId = Module::ID;
Loader::includeModule($moduleId);

$defaultOptions = ModuleDefaults::get();
$app = Application::getInstance();
$request = $app->getContext()->getRequest();

if ($request->isPost() && check_bitrix_sessid()) {
    $optionsToSave = [
        'cancel_after_hours' => (int)($request->getPost('cancel_after_hours') ?: $defaultOptions['cancel_after_hours']),
        'agent_interval'     => (int)($request->getPost('agent_interval') ?: $defaultOptions['agent_interval']),
        'cancel_status'      => $request->getPost('cancel_status') ?: $defaultOptions['cancel_status'],
        'enable_agent'       => $request->getPost('enable_agent') === 'Y' ? 'Y' : $defaultOptions['enable_agent'],
        'start_agent'        => $request->getPost('start_agent') ?: $defaultOptions['start_agent'],
    ];

    foreach ($optionsToSave as $key => $value) {
        Option::set($moduleId, $key, $value);
    }

    if ($optionsToSave['enable_agent'] === 'Y') {
        UnpaidOrdersAgentHelper::registerAgent();
    } else {
        UnpaidOrdersAgentHelper::unregisterAgent();
    }

    \CAdminMessage::ShowMessage([
        'MESSAGE' => 'Настройки успешно сохранены',
        'TYPE' => 'OK'
    ]);
}

// Получение текущих значений для формы
$agentEnabled = Option::get($moduleId, 'enable_agent', $defaultOptions['enable_agent']);
$cancelAfterHours = Option::get($moduleId, 'cancel_after_hours', $defaultOptions['cancel_after_hours']);
$agentInterval = Option::get($moduleId, 'agent_interval', $defaultOptions['agent_interval']);
$agentStart = Option::get($moduleId, 'start_agent', $defaultOptions['start_agent']);
$cancelStatus = Option::get($moduleId, 'cancel_status', $defaultOptions['cancel_status']);


// Получаем список статусов заказов
$statuses = [];
if (Loader::includeModule('sale')) {
    $dbStatus = \CSaleStatus::GetList(['SORT'=>'ASC', 'NAME'=>'ASC'], ['TYPE'=>'O'], false, false, ['ID','NAME']);
    while ($status = $dbStatus->Fetch()) {
        $statuses[$status['ID']] = $status['NAME'] . ' (' . $status['ID'] . ')';
    }
}


$aTabs = [
    [
        "DIV" => "edit1",
        "TAB" => Loc::getMessage("DROGALOV_ORDERHANDLER_STATUS_ORDER_TAB"),
        "ICON" => "main_settings",
        "TITLE" => Loc::getMessage("DROGALOV_ORDERHANDLER_STATUS_ORDER_TITLE"),
    ],
    [
        "DIV" => "donate",
        "TAB" => 'Еще таб',
        "ICON" => "main_user_edit",
        "TITLE" => 'Заголовок',
    ],
];

$tabControl = new CAdminTabControl("tabControl", $aTabs);

$tabControl->begin();

?>

<form
        method="post"
        action="<?= sprintf(
            '%s?mid=%s&lang=%s',
            $request->getRequestedPage(),
            urlencode($mid),
            LANGUAGE_ID
        ) ?>&<?= $tabControl->ActiveTabParam() ?>"
        enctype="multipart/form-data"
        name="editform"
        class="editform"
>
    <?php
    echo bitrix_sessid_post();
    $tabControl->beginNextTab();
    ?>
    <tr class="heading">
        <td colspan="2"><b>Включение обработчика</b></td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('DROGALOV_ORDERHANDLER_CANCEL_AGENT_TOGGLE') ?>:</td>
        <td>
            <input type="checkbox" name="enable_agent" value="Y" <?= $agentEnabled === 'Y' ? 'checked' : '' ?> />
        </td>
    </tr>
    <tr class="heading">
        <td colspan="2"><b>Настройки обработки</b></td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('DROGALOV_ORDERHANDLER_CANCEL_AFTER_HOURS') ?>:</td>
        <td><input type="text" name="cancel_after_hours"
                   value="<?= htmlspecialcharsbx($cancelAfterHours) ?>" size="10"/></td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('DROGALOV_ORDERHANDLER_CANCEL_AGENT_INTERVAL') ?>:</td>
        <td><input type="text" name="agent_interval" value="<?= htmlspecialcharsbx($agentInterval) ?>" size="10"/></td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('DROGALOV_ORDERHANDLER_CANCEL_AGENT_START') ?>:</td>
        <td>
            <div class="adm-input-wrap adm-input-wrap-calendar">
                <input class="adm-input adm-input-calendar" type="text" name="start_agent"
                       value="<?= htmlspecialcharsbx($agentStart) ?>" size="22"/>
                <button type="button" class="adm-calendar-icon"
                        title="Нажмите для выбора даты"></button>
                <script>
                    (function () {
                        const input = document.querySelector(`input[name="start_agent"]`);
                        const button = input.nextElementSibling;
                        let picker = null;
                        const getPicker = () => {
                            if (picker === null) {
                                picker = new BX.UI.DatePicker.DatePicker({
                                    targetNode: input,
                                    inputField: input,
                                    enableTime: true,
                                    useInputEvents: false,
                                });
                            }

                            return picker;
                        };

                        BX.Event.bind(button, "click", () => getPicker().show());
                    })();
                </script>
            </div>
        </td>
    </tr>
    <tr class="heading">
        <td colspan="2"><b>Смена статуса</b></td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('DROGALOV_ORDERHANDLER_CANCEL_STATUS') ?>:</td>
        <td>
            <select name="cancel_status">
                <?php
                foreach ($statuses as $id => $name): ?>
                    <option value="<?= $id ?>" <?= $id === $cancelStatus ? 'selected' : '' ?>><?= htmlspecialcharsbx(
                            $name
                        ) ?></option>
                <?php
                endforeach; ?>
            </select>
        </td>
    </tr>


    <?php
    $tabControl->BeginNextTab();

    echo 'Еще один таб';


    $tabControl->Buttons([
        "btnSave" => true,
        "btnApply" => true,
        "btnCancel" => true,
        "back_url" => $APPLICATION->GetCurUri(),
    ]);
    $tabControl->End();
    ?>
</form>