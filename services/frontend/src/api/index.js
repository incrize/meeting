import ApolloClient from 'apollo-boost';
import { queryGet } from './employees';
import { config } from '../config'

export const apiClient = new ApolloClient({
  uri: config.apiUrl,
  request: (operation) => {
    const token = config.apiToken;
    operation.setContext({
      headers: {
        authorization: token ? `Bearer ${token}` : ''
      }
    })
  }
});

export { queryGet };