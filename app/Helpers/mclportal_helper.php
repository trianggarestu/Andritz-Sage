<?php

function selected($a, $b, $opt=0)
	{
		if ($a == $b)
		{
			if ($opt)
				echo "checked='checked'";
			else echo "selected='selected'";
		}
	}