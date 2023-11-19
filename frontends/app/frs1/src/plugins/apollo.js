import {ApolloClient, InMemoryCache} from "@apollo/client/core";
import {createApolloProvider} from "@vue/apollo-option";

const cache = new InMemoryCache();

// const hasuraHeaders = new ApolloLink(() => {
//     operation.setContext({
//         headers: {
//             Authorization: `x-hasura-admin-secret ` + 'J36JMbAxaUph1DdgfGspsbTOHvKYxUzPuMJJTytzPjivOX91OtlIFV0ieFsIjdYG'
//         },
//     })
//     return forward(operation)
// })


//  "X-API-Key": "0758292e4ab504af63a41e4f6553aa84"


export const apolloClient = new ApolloClient({
  cache,
  // uri: "http://frs.local/pimcore-graphql-webservices/api",
  uri: "http://192.168.2.172:8989/pimcore-graphql-webservices/api",
  headers: {
    "x-api-key": "d43eef154c15bb04e1529d5724cdd877"
  }
});

export default createApolloProvider({
  defaultClient: apolloClient,
  clients: {
    apolloClient
  }
});

