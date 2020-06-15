<template>
  <div>
    <el-tabs v-model="tab" style="margin-bottom: 20px;">
      <el-tab-pane v-for="{ label, value, component } in tabs" :key="value" :label="label" :name="value">
        <component :is="component" />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import DefaultMenu from "./menu-components/DefaultMenu";
import IndividuationMenuList from "./menu-components/IndividuationMenuList";

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
          component: IndividuationMenuList
        }
      ],
      tab: 'default'
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
