import envJson from './env.json';
const environmentMode = __DEV__ ? 'DEVELOPMENT' : 'PRODUCTION';

type environment = {
  ENVIRONMENT: 'DEVELOPMENT' | 'PRODUCTION';
  APP_NAME: string;
  API_BACKEND: string;
  APP_VERSION: string;
  APP_VERSION_BUILD: number;
};

const env: environment = {
  ENVIRONMENT: environmentMode,
  APP_NAME: envJson[environmentMode].APP_NAME,
  API_BACKEND: envJson[environmentMode].API_BACKEND,
  APP_VERSION: envJson[environmentMode].APP_VERSION,
  APP_VERSION_BUILD: envJson[environmentMode].APP_VERSION_BUILD,
};

export default env;
