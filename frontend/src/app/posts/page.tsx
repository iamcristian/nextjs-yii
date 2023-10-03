"use client";
import { useEffect, useState } from "react";

interface Comment {
  id: number;
  title: string;
  body: string;
  created_at: string | null;
  updated_at: string | null;
  post_id: number;
}

const Posts = () => {
  const [info, setInfo] = useState<Comment[] | null>(null);

  useEffect(() => {
    fetch("http://localhost:8080/comment")
      .then((response) => response.json())
      .then((data: Comment[]) => setInfo(data));
  }, []);

  console.log(info);

  return (
    <>
      <h1>Posts</h1>
      <hr />
      {info &&
        (info as Comment[]).map((data: Comment) => (
          <div key={data.id}>
            <h2>{data.title}</h2>
            <p>{data.body}</p>
            <hr />
          </div>
        ))}
    </>
  );
};

export default Posts;
