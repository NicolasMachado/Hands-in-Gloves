<?php
function paginate($url, $link, $total, $current, $adj=3) {
	
	$prev = $current - 1; 
	$next = $current + 1; 
	$penultimate = $total - 1; 
	$pagination = '';

	if ($total > 1) 
	{
		$pagination .= "<div class=\"pagination\">\n";

		if ($current == 2) 
		{
			$pagination .= "<a href=\"{$url}\">◄</a>";
		} 
		elseif ($current > 2) 
		{
			$pagination .= "<a href=\"{$url}{$link}{$prev}\">◄</a>";
		} 
		else 
		{
			$pagination .= '<span class="inactive">◄</span>';
		}

		// Case 1 -> no truncate (less than 12 pages)
		if ($total < 7 + ($adj * 2)) {
			
			$pagination .= ($current == 1) ? '<span class="active">1</span>' : "<a href=\"{$url}\">1</a>";

			for ($i=2; $i<=$total; $i++) 
			{
				if ($i == $current) 
				{
					$pagination .= "<span class=\"active\">{$i}</span>";
				} 
				else 
				{
					$pagination .= "<a href=\"{$url}{$link}{$i}\">{$i}</a>";
				}
			}
		}
		
		// Case 2 -> truncate (at least 12 pages)
		else 
		{
			if ($current < 2 + ($adj * 2)) 
			{
				$pagination .= ($current == 1) ? "<span class=\"active\">1</span>" : "<a href=\"{$url}\">1</a>";

				for ($i = 2; $i < 4 + ($adj * 2); $i++) 
				{
					if ($i == $current) 
					{
						$pagination .= "<span class=\"active\">{$i}</span>";
					} 
					else 
					{
						$pagination .= "<a href=\"{$url}{$link}{$i}\">{$i}</a>";
					}
				}
				
				// Ellipsis
				$pagination .= '&hellip;';
				
				// Last 2 numbers
				$pagination .= "<a href=\"{$url}{$link}{$penultimate}\">{$penultimate}</a>";
				$pagination .= "<a href=\"{$url}{$link}{$total}\">{$total}</a>";
			}
			elseif ( (($adj * 2) + 1 < $current) && ($current < $total - ($adj * 2)) ) 
			{
				$pagination .= "<a href=\"{$url}\">1</a>";
				$pagination .= "<a href=\"{$url}{$link}2\">2</a>";
				$pagination .= '&hellip;';

				// Middle page, 3 after, 3 before
				for ($i = $current - $adj; $i <= $current + $adj; $i++) 
				{
					if ($i == $current) 
					{
						$pagination .= "<span class=\"active\">{$i}</span>";
					} 
					else 
					{
						$pagination .= "<a href=\"{$url}{$link}{$i}\">{$i}</a>";
					}
				}

				$pagination .= '&hellip;';

				// And last 2 numbers
				$pagination .= "<a href=\"{$url}{$link}{$penultimate}\">{$penultimate}</a>";
				$pagination .= "<a href=\"{$url}{$link}{$total}\">{$total}</a>";
			}
			else 
			{
				// Number 1 and 2
				$pagination .= "<a href=\"{$url}\">1</a>";
				$pagination .= "<a href=\"{$url}{$link}2\">2</a>";
				$pagination .= '&hellip;';

				// 9 last
				for ($i = $total - (2 + ($adj * 2)); $i <= $total; $i++) 
				{
					if ($i == $current) 
					{
						$pagination .= "<span class=\"active\">{$i}</span>";
					} 
					else 
					{
						$pagination .= "<a href=\"{$url}{$link}{$i}\">{$i}</a>";
					}
				}
			}
		}

		// Next button
		if ($current == $total)
		{
			$pagination .= "<span class=\"inactive\">►</span>\n";
		}
		else
		{
			$pagination .= "<a href=\"{$url}{$link}{$next}\">►</a>\n";
		}

		$pagination .= "</div>\n";
	}

	return ($pagination);
}
?>