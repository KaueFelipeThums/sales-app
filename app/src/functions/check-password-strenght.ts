const checkPasswordStrength = (value: string): number => {
  const uppercase = /[A-Z]/;
  const lowercase = /[a-z]/;
  const number = /[0-9]/;

  let percentStrength = 0;

  if (uppercase.test(value)) {
    percentStrength += 25;
  }

  if (lowercase.test(value)) {
    percentStrength += 25;
  }

  if (number.test(value)) {
    percentStrength += 25;
  }

  if (value.length >= 8) {
    percentStrength += 25;
  }

  return percentStrength;
};

export { checkPasswordStrength };
