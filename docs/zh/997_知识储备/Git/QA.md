# git status 不好使

1. 首先, 提交全部更新
2. 执行 git rm -r --cached . //从 index 内删除所有变更过的文件
3. 执行 git add .
4. 执行 git commit -m ". //SourceTree自带推送按钮，这一步命令行可以省略.