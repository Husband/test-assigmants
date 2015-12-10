##PHP/JavaScript разработчик Modera - тестовое задание

###Задание 1
На входе дается текстовый файл,где каждая строка отформатирована следующим образом: node_id|parent_id|node_name

Необходимо из этих данных построить json дерево (формат произвольный).

Пример:```{‘name’:‘xxx’,id:‘yyy’,‘children’:[...]}```

Ноды на одном уровне должны быть отсортированы по алфавиту. Пример данных:

```
1|0|Electronics
2|0|Video
3|0|Photo
4|1|MP3player
5|1|TV
6|4|iPod
7|6|Shuffle
8|3|SLR
9|8|DSLR
10|9|Nikon
11|9|Canon
12|11|20D
```

###Задание 2 
Вам нужно сделать Symfony Bundle, который будет принимать имя файла с данными из задания 1 в качестве параметра и возвращать результат в виде json дерева.

###Задание 3
Получить json данные из задания 2 и вывести через компоненту ExtJStree.

###Отчет
1. *Первое задание выполнил ровно за 2 часа.* [DemoBundle/Utils/RawToJSONTree.php](https://github.com/Husband/Modera-Test-Job/blob/master/DemoBundle/Utils/RawToJSONTree.php).

2. Во втором задании больше всего времени ушло на то чтобы решить где хранить data.txt и как его читать.
*Времени ушло 1 час 50 минут.* Код контроллера [DemoBundle/Controller/DefaultController.php](https://github.com/Husband/Modera-Test-Job/blob/master/DemoBundle/Controller/DefaultController.php).

3. В третьем задании пришлось переделывать формат отдаваемых данных из второго задания чтобы не разбиратся с настройкой компонента tree.
*На задание потратил 1 час 25 минут.* [DemoBundle/Controller/DefaultController.php](https://github.com/Husband/Modera-Test-Job/blob/master/DemoBundle/Resources/views/Default/index.html.twig).

Symfony2 и ExtJs видел первый раз.

Пользовался официальной документацией и StackOverflow. Для третьего задания копипастнул пример из какого-то блога.
