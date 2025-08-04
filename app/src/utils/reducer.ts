type ActionRecord<T> = {
  type: 'CREATE' | 'UPDATE' | 'DELETE';
  payload?: T;
  replace?: T;
  key?: keyof T;
};

type ActionArray<T> = {
  type: 'LOAD' | 'CONCAT';
  payload?: T[];
  key?: keyof T;
};

type Action<T> = ActionRecord<T> | ActionArray<T>;

const reducer = <T extends Record<string, any>>(state: T[], action: Action<T>): T[] => {
  const key = (action.key ?? 'id') as keyof T;

  switch (action.type) {
    case 'LOAD':
      return Array.isArray(action.payload) ? [...action.payload] : state;

    case 'CREATE':
      return action.payload && !Array.isArray(action.payload) ? [...state, action.payload] : state;
    case 'UPDATE':
      if (action.payload && !Array.isArray(action.payload)) {
        return state.map((item) => (item[key] === action.payload?.[key] ? { ...item, ...action.payload } : item));
      }
      return state;

    case 'DELETE':
      if (action.payload && !Array.isArray(action.payload)) {
        return state.filter((item) => item[key] !== action.payload?.[key]);
      }
      return state;

    case 'CONCAT':
      if (Array.isArray(action.payload)) {
        return [...state, ...action.payload];
      }
      return state;
    default:
      return state;
  }
};

export { reducer, type Action };
