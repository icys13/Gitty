<div class="repo-init module">
	<div class="repo-init-content module-bd">
		<p class="hint">安装并设置 Git 到您的 PC 上</p>
		<h3>
			<span class="icon">1</span>
			全局设置:
		</h3>
		<pre>
			<code>
				git config --global user.name "<?=$username?>"
				git config --global user.email <?=$email?>
			</code>
		</pre>
		<h3>
			<span class="icon">2</span>
			接下来:
		</h3>
		<div class="alternative">
			<h4>• 在本地创建新的 Git 仓库</h4>
			<pre>
				<code>
					mkdir test
					cd test
					git init
					touch README.md
					git add README.md
					git commit -m 'first commit'
					git remote add origin git@hostname:<?=$username.'/'.$repo_name.'.git'?>			
					test.git
					git push -u origin master
				</code>
			</pre>
		</div>
		<div class="or">
			<span class="icon">或</span>
		</div>
		<div class="alternative">
			<h4>• 在本地已有 Git 仓库</h4>
			<pre>
				<code>
					cd existing_git_repo
					git remote add origin git@hostname:<?=$username.'/'.$repo_name.'.git'?>					git push -u origin master
				</code>
			</pre>
		</div>
		<h3>
			<span class="icon">3</span>
			当你完成的时候:
		</h3>
		<a class="btn btn-primary" href="ordinary/repository/index/<?=$username.'/'.$repo_name?>">继续</a>
	</div>
</div>
