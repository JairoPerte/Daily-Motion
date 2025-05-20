import { data } from "./todos";

function App() {
  return (
    <main className="py-10 h-screen">
      <h1 className="font-bold text-3xl text-center">ToDos</h1>
      <div>
        <div className="max-w-lg mx-auto">
          {data.map((todo) => (
            <p key={todo.id} className="text-lg">
              {todo.title}
            </p>
          ))}
        </div>
      </div>
    </main>
  );
}

export default App;
