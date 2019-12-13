import React from 'react';
import { ApolloProvider } from '@apollo/react-hooks';
import { apiClient } from './api';
import { Employees } from './components/employees';

function App() {
  return (
    <ApolloProvider client={apiClient}>
      <div>
        <Employees/>
      </div>
    </ApolloProvider>
  );
}

export default App;
