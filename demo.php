<?

$array['title'] = 'Заголовок';
$array['titles']['subtitle'] = 'Подзаголовок';
$array['list']['Меню'] = array(
	'name' => 'punkt 1',
	'item' => array(
		'Один' => array( 'name' => 'punkt #', 'number' => '2' ),
		'Два' => array( 'name' => 'punkt #', 'number' => '3' ),
	),
	'end' => 'punkt 4'
);

$templateDemo = "
	<h1>[title]</h1>
	<h2>[titles|subtitle]</h2>
	[%list%
		<ul>
			<li>{name}</li>
				<ul>
					{item|<li>(name) (number)</li>}
				</ul>
			<li>{end}</li>
		</ul>
	]
";

echo $templateDemo;

?>
