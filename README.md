# Консольный калькулятор для игры Albion Online

## Описание проекта

Этот проект представляет собой консольный калькулятор крафта для популярной многопользовательской онлайн-игры *Albion Online*.
Основная цель приложения — предоставить пользователю необходимую информацию для анализа процесса создания предметов, позволяя ему быстро и эффективно разложить выбранный предмет на его комплектующие и получить статистику по стоимости и количеству необходимых ресурсов.

## Функциональность

Калькулятор предлагает следующие функции:

- **Анализ предметов**: Позволяет пользователям вводить название предмета, который они собираются крафтить, и автоматически разбивает его на необходимые компоненты.
- **Подсчет статистики**: Программа собирает данные по каждому комплектующему элементу, включая их количество и текущую рыночную цену в конкретном городе.
- **Сравнение стоимости**: После анализа, калькулятор сравнивает общую стоимость комплектующих с ценой основного предмета, что позволяет игрокам принимать обоснованные решения о целесообразности крафта.

## Зачем это нужно?

В условиях изменчивой экономической обстановки на игровом рынке, где цены на предметы регулярно меняются, наличие инструмента для быстрой оценки стоимости крафта помогает пользователю:

- Экономить значительное количество времени при планировании крафта.
- Избегать ненужных затрат на компоненты, которые могут превышать стоимость готового предмета.
- Оптимизировать стратегию игры и улучшать игровой опыт.

## Технологии

В проекте используются следующие технологии:

- **PHP**.
- **Elasticsearch** для поиска и анализа данных.
- Работа с **API** для получения актуальной информации о ценах предметов.
- Работа с форматами **JSON** и **XML** для получения и обработки информации.

## Установка и использование
Поскольку приложение является консольным, его запуск и использование очень просты. 

### Загрузка данных
Для загрузки необходимых данных в Elasticsearch достаточно выполнить следующую команду в терминале:
```
php run.php
```
Загрузка данных может занять некоторое время. После завершения загрузки приложение будет готово к использованию, и вы сможете приступить к анализу предметов и крафту.

### Пример использования
```
php index.php 1 слиток 5 caerleon
```
Параметры команды:
- `1` — **количество предметов**, которые вы хотите создать.
- `слиток` — **название предмета**.
- `5` — **уровень искомого предмета**.
- `caerleon` — **название города**, по которому будет получена средняя цена предметов.
