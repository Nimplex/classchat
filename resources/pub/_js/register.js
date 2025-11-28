document.addEventListener("DOMContentLoaded", () => {
  const pass = document.getElementById("pass_input");
  const requirements = {
    pass_l: x => /[a-z]/.test(x),
    pass_u: x => /[A-Z]/.test(x),
    pass_n: x => /[0-9]/.test(x),
    pass_s: x => ["@", "$", "!", "%", "*", "?", "&"].some(c => x.includes(c)),
    pass_8: x => /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(x),
  };


  pass.addEventListener("input", () => {
    for (const [el_id, fn] of Object.entries(requirements)) {
      const el = document.getElementById(el_id);
      if (fn(pass.value)) {
        el.classList.add("ok");
      } else {
        el.classList.remove("ok");
      }
    }
  })
})
