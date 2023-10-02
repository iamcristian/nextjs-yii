const posts = () => {

  const data = fetch('http://localhost:8080/comment')
    .then(response => response.json())
    .then(data => console.log(data))

  
  return (
    <>
      <h1>Posts</h1>
      <hr />
    </>
  );
};

export default posts;
