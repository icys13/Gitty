<div class="code-preview">
	<div class="file" id="file">
		<div class="fr">
			<a class="btn" href="<?=base_url()?>index.php/ordinary/blob/raw/<?=$username?>/<?=$reponame?>/<?=$SHA?>">原始文档</a>
			<button class="fullscreen btn"><i class="icon-fullscreen"></i>全屏查看</button>
			<button class="exit-fullscreen hidden new-issue-btn"><i class="icon-exit-fullscreen">退出全屏</i></button>
		</div>
		<div class="file-meta"></div>
		<div class="commit"></div>
		<article class="file-content">
			<table class="text highlighttable">
				<tbody>
					<tr>
						<td class="linenos">
							<div class="linenodiv"><pre><?=$line?></pre></div>
						</td>
						<td class="code">
							<div class="text highlight"><pre><?=$code?></pre></div>
						</td>
					</tr>
				</tbody>	
			</table>
		</article>
	</div>
</div>
</div>
</div>
</div>
</div>
