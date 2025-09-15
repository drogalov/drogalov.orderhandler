<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

$tabControl->BeginNextTab();

use \Bitrix\Main\Localization\Loc;
?>
<style>
    :root{
        --bg:#ffffff;
        --accent:#0b63c6;
        --muted:#6b7280;
        --border:#e6eef9;
        --card:#f8fbff;
        --radius:10px;
        --maxw:980px;
        --mono: ui-monospace, SFMono-Regular, Menlo, Monaco, "Roboto Mono", "Helvetica Neue", monospace;
    }
    .card{background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:20px; box-shadow:0 6px 18px rgba(11,99,198,0.06);}
    header{display:flex;gap:16px;align-items:center;margin-bottom:12px;}
    .logo{width:64px;height:64px;border-radius:8px;background-image: linear-gradient(to left top, #51d4da, #77d3f6, #abd0fd, #d4cdf5, #eccfe7);display:flex;align-items:center;justify-content:center;}
    .logo img{width:48px;height:48px;}
    h1{font-size:20px;margin:0;}
    p.lead{margin:8px 0 18px;color:var(--muted);}

    .grid{display:grid;grid-template-columns:1fr auto;gap:18px;}
    @media (max-width:880px){ .grid{grid-template-columns:1fr;} }

    section{margin-bottom:14px;}
    h2{font-size:16px;margin:12px 0;}
    ul{margin:8px 0 12px 18px;color:var(--muted);}
    li{margin:6px 0;line-height:1.45;}
    .badge{display:inline-block;background:rgba(11,99,198,0.12);color:var(--accent);padding:6px 10px;border-radius:999px;font-weight:600;font-size:13px;margin-right:8px;}
    .kbd{font-family:var(--mono);background:#fff;border:1px solid #e6eef9;padding:2px 6px;border-radius:6px;font-size:13px;color:var(--muted);}

    .meta{background:#fff;border:1px solid var(--border);padding:12px;border-radius:8px;}
    .meta dt{font-weight:700;margin-top:8px;color:#0b1726;}
    .meta dd{margin:8px 0 14px;color:var(--muted);}
    footer{margin-top:18px;color:var(--muted);font-size:13px;}
</style>
<article class="card" role="article" aria-labelledby="title">
    <header>
        <div class="logo" aria-hidden="true"><img src="https://drogalov.pro/favicon.ico" alt=""></div>
        <div>
            <h1 id="title">Drogalov Order Handler — автоматическая обработка заказов</h1>
            <p class="lead">Модуль для 1C-Bitrix: автоматическая смена статуса неоплаченных заказов по таймеру через агент.</p>
        </div>
    </header>

    <div class="grid">
        <main>
            <section aria-labelledby="func">
                <h2 id="func"><span class="badge">Функционал</span></h2>
                <ul>
                    <li>Автоматическая смена статуса заказов, не оплаченных в течение заданного времени (в часах).</li>
                    <li>Настройка интервала агента (секунды) и времени следующего запуска.</li>
                    <li>Выбор статуса, который будет установлен неоплаченным заказам (например, «AN» — аннулирован).</li>
                    <li>Включение/выключение обработчика через чекбокс в настройках модуля.</li>
                    <li>Логирование обработки заказов в отдельные лог-файлы.</li>
                </ul>
            </section>

            <section aria-labelledby="advantages">
                <h2 id="advantages">Преимущества</h2>
                <ul>
                    <li>Снижение ручной рутинной работы менеджеров — статусы обновляются автоматически.</li>
                    <li>Совместимость со стандартным механизмом агентов Bitrix.</li>
                    <li>Централизованная конфигурация и лёгкая поддержка (Module::ID, ModuleDefaults, UnpaidOrdersAgentHelper).</li>
                </ul>
            </section>

            <section aria-labelledby="install">
                <h2 id="install">Установка и настройка</h2>
                <ol>
                    <li>Скопируйте папку модуля в директорию <code class="kbd">/local/modules/</code>.</li>
                    <li>В админке Bitrix установите модуль через «Маркетплейс → Установленные решения» или «Установить модуль».</li>
                    <li>Откройте настройки модуля и задайте параметры: интервал, время старта, статус и включение агента.</li>
                </ol>
            </section>
        </main>

        <aside class="meta" aria-labelledby="metaTitle">
            <h2 id="metaTitle">Параметры модуля</h2>
            <dl>
                <dt>MODULE ID</dt>
                <dd><code class="kbd">drogalov.orderhandler</code></dd>

                <dt>Функция агента</dt>
                <dd><code class="kbd">Drogalov\OrderHandler\Agent\ProcessUnpaidOrders::run();</code></dd>

                <dt>Логирование</dt>
                <dd><code class="kbd">/upload/logs/order_handler/</code></dd>
            </dl>
        </aside>
    </div>

    <footer>
        <p>Версия: <strong>1.3</strong> · Автор: <strong>drogalov.pro</strong> · Контакты: <a href="mailto:iam@drogalov.pro"><strong>iam@drogalov.pro</strong></a></p>
    </footer>
</article>