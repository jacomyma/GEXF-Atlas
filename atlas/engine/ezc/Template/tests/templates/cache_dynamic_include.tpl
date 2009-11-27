{use $a, $b}
{cache_template}

[{$a}]
[{$b}]

{var $number, $t}
{dynamic}
{include "tmp_included.tpl" 
	send 
		  $a, 
		  $b,
		 "Hello" as $p, 
		 "World" as $q

	receive $number, $t
}
[{$number}]
[{$t}]
{/dynamic}

[{$number}]
[{$t}]
