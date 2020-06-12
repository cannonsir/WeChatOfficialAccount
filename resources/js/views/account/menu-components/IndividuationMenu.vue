<template>
  <div>
    <avue-crud :data="data" :option="option" v-model="obj">
      <template slot="menuLeft">
        <el-button icon="el-icon-plus" type="primary" size="small" @click="handleAdd">新增</el-button>
      </template>

      <template v-slot:menu="{ type, size }">
        <el-button icon="el-icon-view" :size="size" :type="type" @click="handleView">查看</el-button>
        <el-button icon="el-icon-delete" :size="size" :type="type" @click="handleDelete">删除</el-button>
      </template>
    </avue-crud>
  </div>
</template>

<script>
export default {
  name: "IndividuationMenu",
  data() {
    return {
      obj:{},
      data: [
        {
          individuation: '张三'
        },
        {
          individuation: '李四'
        },
      ],
      option:{
        title:'表格的标题',
        page:false,
        align:'center',
        menuAlign:'center',
        delBtn: false,
        editBtn: false,
        columnBtn: false,
        addBtn: false,
        column:[
          {
            label:'显示对象',
            prop: 'individuation'
          }
        ]
      }
    }
  },
  methods: {
    handleAdd() {
      //
    },
    handleView() {
      //
    },
    handleDelete() {
      //
    }
  }
}
</script>

<style scoped>

</style>
