算法：

1. 生成关系：
菜单项1，直接孩子
菜单项2，直接孩子
菜单项3，直接孩子
菜单项4，直接孩子
菜单项5，直接孩子
菜单项6，直接孩子
菜单项7，没有孩子
菜单项8，没有孩子

for (item in $items) {
   for (i in $items) {
      if $i.parent = item then item.child[] = i;
  }
}

2. 组合菜单
对于每一个父亲，找到它的孩子，把孩子组合在一个模板里。其中有一种特殊的父亲：空父亲。
for (item in $parents) {
  gen_panel(item, item的直接孩子们)
}


3. 结果
pannel1   菜单项1，菜单项2，....
pannel2   菜单项3，菜单项4，....
...
