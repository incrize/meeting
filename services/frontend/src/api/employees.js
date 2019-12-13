import { gql } from "apollo-boost";

export const queryGet = gql `
  {
    employeesList {
      items {
        id,
        name
      }
    }
  }
`;

export const queryAdd = gql `
  {
    employeesList {
      items {
        id,
        name
      }
    }
  }
`;