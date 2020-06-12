<template>
  <div>
    <el-tabs v-model="tab">
      <el-tab-pane v-for="{ label, value, component } in tabs" :key="value" :label="label" :name="value">
        <component :is="component" />
      </el-tab-pane>
    </el-tabs>

    <el-alert type="success" :closable="false" style="margin-bottom: 10px">
      <div slot="title">
        <a href="https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Creating_Custom-Defined_Menu.html" target="_blank">
          <el-button type="text">微信自定义菜单文档链接</el-button>
        </a>
        |
        <a href="https://mp.weixin.qq.com/debug/cgi-bin/sandboxinfo?action=showinfo&t=sandbox/index" target="_blank">
          <el-button type="text">微信公众测试号链接</el-button>
        </a>
      </div>
    </el-alert>
  </div>
</template>

<script>
import DefaultMenu from "./menu-components/DefaultMenu";
import IndividuationMenu from "./menu-components/IndividuationMenu";

export default {
  name: 'Menu',
  data() {
    return {
      tabs: [
        {
          label: '默认菜单',
          value: 'default',
          component: DefaultMenu
        },
        {
          label: '个性化菜单',
          value: 'individuation',
          component: IndividuationMenu
        }
      ],
      tab: 'default'
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
