<template>
  test5
  <v-container class="">
    <v-responsive class="">
      <pre>
        1{{ meal }}2
      </pre>
    </v-responsive>
  </v-container>
</template>

<script setup>
  import { onMounted, ref } from 'vue';
  import apollo from "@/plugins/apollo";
  import Meal from "@/graphql/queries/meal.graphql";

  const meal = ref(null);

  function loadData(){
    apollo.clients.defaultClient.query({
      query: Meal,
      fetchPolicy: 'no-cache',
    }).then((result) => {
      meal.value = result.data.meal;
      console.log(result);
    });
  }
  onMounted(() => {
    loadData();
    console.log('HelloWorld.vue mounted');
  })
</script>
