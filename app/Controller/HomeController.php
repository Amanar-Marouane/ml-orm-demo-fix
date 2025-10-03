<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Review;
use App\Entity\Category;
use App\Entity\BookDetail;
use App\Repository\UserRepository;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use App\Repository\CategoryRepository;
use MonkeysLegion\Router\Attributes\Route;
use MonkeysLegion\Http\Message\Response;
use MonkeysLegion\Http\Message\Stream;
use MonkeysLegion\Query\QueryBuilder;
use MonkeysLegion\Template\Renderer;

/**
 * HomeController is responsible for rendering the home page.
 */
final class HomeController
{
    public function __construct(private Renderer $renderer) {}

    /**
     * Render the home page.
     *
     * @return Response
     */
    #[Route(
        methods: 'GET',
        path: '/',
        name: 'home',
        summary: 'Render home page',
        tags: ['Page']
    )]
    public function index(): Response
    {
        $qb = ML_CONTAINER->get(QueryBuilder::class);

        $userRepo = new UserRepository($qb);
        $authorRepo = new AuthorRepository($qb);
        $bookRepo = new BookRepository($qb);
        $reviewRepo = new ReviewRepository($qb);
        $categoryRepo = new CategoryRepository($qb);

        // Test all relationship types and depths
        $testResults = $this->testAllRelationships($userRepo, $authorRepo, $bookRepo, $reviewRepo, $categoryRepo);

        // 1) Render template
        $html = $this->renderer->render('home', [
            'title' => 'Complete Relationship Depth Test',
            'testResults' => $testResults,
        ]);

        // 2) Build a Stream from the HTML
        $body = Stream::createFromString($html);

        // 3) Return the MonkeysLegion PSR-7 Response
        return new Response(
            $body,
            200,
            ['Content-Type' => 'text/html']
        );
    }

    /**
     * Benchmark a callable and return its result along with execution time
     * 
     * @param callable $fn The function to benchmark
     * @param string $description Description of the operation being benchmarked
     * @return array [result, executionTime, description]
     */
    private function benchmark(callable $fn, string $description): array
    {
        $startTime = microtime(true);
        $result = $fn();
        $executionTime = microtime(true) - $startTime;
        return [$result, $executionTime, $description];
    }

    private function testAllRelationships(
        UserRepository $userRepo,
        AuthorRepository $authorRepo,
        BookRepository $bookRepo,
        ReviewRepository $reviewRepo,
        CategoryRepository $categoryRepo
    ): array {
        $results = [];
        $benchmarks = [];

        try {
            // Create test data
            $this->createTestData($userRepo, $authorRepo, $bookRepo, $reviewRepo, $categoryRepo);

            $results[] = "🔍 COMPLETE RELATIONSHIP DEPTH TEST";
            $results[] = "===================================";

            // Test OneToMany: Author -> Books
            $results[] = "";
            $results[] = "📈 OneToMany: Author -> Books (Depth 1)";
            [$author, $time, $desc] = $this->benchmark(
                fn() => $authorRepo->findOneBy(['email' => 'author@example.com'], true),
                'Author with books'
            );
            $benchmarks[] = ["Author → Books", $time, $desc];

            if (!empty($author->books)) {
                $results[] = "✅ Author has " . count($author->books) . " books loaded";
                $results[] = "⏱️ Query time: " . number_format($time * 1000, 2) . " ms";
                foreach ($author->books as $book) {
                    $results[] = "   📚 {$book->title} (ID: {$book->id})";
                }
            } else {
                $results[] = "❌ Author has NO books loaded";
            }

            // Test ManyToOne: Book -> Author
            $results[] = "";
            $results[] = "📈 ManyToOne: Book -> Author (Depth 1)";
            [$book, $time, $desc] = $this->benchmark(
                fn() => $bookRepo->findOneBy(['title' => 'Test Book 1'], true),
                'Book with author'
            );
            $benchmarks[] = ["Book → Author", $time, $desc];

            if (isset($book->author)) {
                $results[] = "✅ Book has author loaded: {$book->author->name}";
                $results[] = "⏱️ Query time: " . number_format($time * 1000, 2) . " ms";
            } else {
                $results[] = "❌ Book has NO author loaded";
            }

            // Test OneToMany: Book -> Reviews
            $results[] = "";
            $results[] = "📈 OneToMany: Book -> Reviews (Depth 1)";
            if (!empty($book->reviews)) {
                $results[] = "✅ Book has " . count($book->reviews) . " reviews loaded";
                foreach ($book->reviews as $review) {
                    $results[] = "   ⭐ Rating: {$review->rating}/5 (ID: {$review->id})";
                }
            } else {
                $results[] = "❌ Book has NO reviews loaded";
            }

            // Test ManyToMany: Author -> Categories
            $results[] = "";
            $results[] = "📈 ManyToMany: Author -> Categories (Depth 1)";
            if (!empty($author->categories)) {
                $results[] = "✅ Author has " . count($author->categories) . " categories loaded";
                foreach ($author->categories as $category) {
                    $results[] = "   🏷️ {$category->name} (ID: {$category->id})";
                }
            } else {
                $results[] = "❌ Author has NO categories loaded";
            }

            // Test ManyToMany reverse: Category -> Authors
            $results[] = "";
            $results[] = "📈 ManyToMany: Category -> Authors (Depth 1)";
            [$category, $time, $desc] = $this->benchmark(
                fn() => $categoryRepo->findOneBy(['name' => 'Fiction'], true),
                'Category with authors'
            );
            $benchmarks[] = ["Category → Authors", $time, $desc];

            if (!empty($category->authors)) {
                $results[] = "✅ Category has " . count($category->authors) . " authors loaded";
                $results[] = "⏱️ Query time: " . number_format($time * 1000, 2) . " ms";
                foreach ($category->authors as $author) {
                    $results[] = "   ✍️ {$author->name} (ID: {$author->id})";
                }
            } else {
                $results[] = "❌ Category has NO authors loaded";
            }

            // Test Depth 2: Author -> Books -> Reviews
            $results[] = "";
            $results[] = "📈 DEPTH 2: Author -> Books -> Reviews";
            $depth2Success = false;
            if (!empty($author->books)) {
                foreach ($author->books as $book) {
                    if (!empty($book->reviews)) {
                        $depth2Success = true;
                        $results[] = "✅ Book '{$book->title}' has " . count($book->reviews) . " reviews";
                        foreach ($book->reviews as $review) {
                            $results[] = "     ⭐ Rating: {$review->rating}/5";
                        }
                    } else {
                        $results[] = "❌ Book '{$book->title}' has NO reviews loaded";
                    }
                }
            }
            if (!$depth2Success) {
                $results[] = "❌ DEPTH 2 FAILED: No reviews found in any books";
            }

            // Test Depth 2: Author -> Books -> Author (circular)
            $results[] = "";
            $results[] = "📈 DEPTH 2: Author -> Books -> Author (Circular Test)";
            if (!empty($author->books)) {
                foreach ($author->books as $book) {
                    if (isset($book->author)) {
                        $results[] = "✅ Book '{$book->title}' has author: {$book->author->name}";

                        // Test if the book's author has books loaded (would be circular)
                        if (!empty($book->author->books)) {
                            $results[] = "     ⚠️ Circular loading detected: Author has " . count($book->author->books) . " books";
                        } else {
                            $results[] = "     ✅ Circular protection: Author has no books loaded";
                        }
                    }
                }
            }

            // Test Depth 3: Author -> Books -> Reviews -> User
            $results[] = "";
            $results[] = "📈 DEPTH 3: Author -> Books -> Reviews -> User";
            $depth3Success = false;
            if (!empty($author->books)) {
                foreach ($author->books as $book) {
                    if (!empty($book->reviews)) {
                        foreach ($book->reviews as $review) {
                            if (isset($review->user)) {
                                $depth3Success = true;
                                $results[] = "✅ Review has user loaded: {$review->user->name}";
                            } else {
                                $results[] = "❌ Review has NO user loaded";
                            }
                        }
                    }
                }
            }
            if (!$depth3Success) {
                $results[] = "❌ DEPTH 3 FAILED: No users found in reviews";
            }

            // Junction table verification for ManyToMany
            $results[] = "";
            $results[] = "📊 JUNCTION TABLE VERIFICATION";
            [$junctionData, $time, $desc] = $this->benchmark(
                function () {
                    $qb = ML_CONTAINER->get(\MonkeysLegion\Query\QueryBuilder::class);
                    return $qb->select(['*'])->from('author_category')->fetchAll();
                },
                'Junction table query'
            );
            $benchmarks[] = ["Junction table query", $time, $desc];

            $results[] = "author_category table has " . count($junctionData) . " records:";
            $results[] = "⏱️ Query time: " . number_format($time * 1000, 2) . " ms";
            foreach ($junctionData as $record) {
                $authorId = is_array($record) ? $record['author_id'] : $record->author_id;
                $categoryId = is_array($record) ? $record['category_id'] : $record->category_id;
                $results[] = "   🔗 Author:{$authorId} ↔ Category:{$categoryId}";
            }

            // Summary
            $results[] = "";
            $results[] = "🎯 SUMMARY";
            $results[] = "==========";

            $oneToManyWorks = !empty($author->books);
            $manyToOneWorks = isset($book->author);
            $manyToManyWorks = !empty($author->categories) && !empty($category->authors);
            $depth2Works = $depth2Success;
            $depth3Works = $depth3Success;

            $results[] = $oneToManyWorks ? "✅ OneToMany (Author->Books): WORKING" : "❌ OneToMany: BROKEN";
            $results[] = $manyToOneWorks ? "✅ ManyToOne (Book->Author): WORKING" : "❌ ManyToOne: BROKEN";
            $results[] = $manyToManyWorks ? "✅ ManyToMany (Author<->Category): WORKING" : "❌ ManyToMany: BROKEN";
            $results[] = $depth2Works ? "✅ Depth 2 loading: WORKING" : "❌ Depth 2 loading: BROKEN";
            $results[] = $depth3Works ? "✅ Depth 3 loading: WORKING" : "❌ Depth 3 loading: BROKEN";
            $results[] = count($junctionData) > 0 ? "✅ Junction tables: HAVE DATA" : "❌ Junction tables: EMPTY";

            // Benchmark summary
            $results[] = "";
            $results[] = "⏱️ BENCHMARKS SUMMARY";
            $results[] = "===================";
            foreach ($benchmarks as [$operation, $execTime, $description]) {
                $results[] = sprintf(
                    "⏱️ %s: %.2f ms (%s)",
                    $operation,
                    $execTime * 1000,
                    $description
                );
            }
        } catch (\Exception $e) {
            $results[] = "❌ ERROR: " . $e->getMessage();
            $results[] = "Stack trace: " . $e->getTraceAsString();
        }

        return $results;
    }

    private function createTestData(
        UserRepository $userRepo,
        AuthorRepository $authorRepo,
        BookRepository $bookRepo,
        ReviewRepository $reviewRepo,
        CategoryRepository $categoryRepo
    ): void {
        // Create User
        $user = $userRepo->findOneBy(['email' => 'reviewer@example.com'], false);
        if (!$user) {
            $user = new User();
            $user->email = 'reviewer@example.com';
            $user->name = 'John Reviewer';
            $user->passwordHash = password_hash('password123', PASSWORD_DEFAULT);
            $userRepo->save($user);
        }

        // Create Author
        $author = $authorRepo->findOneBy(['email' => 'author@example.com'], false);
        if (!$author) {
            $author = new Author();
            $author->name = 'Jane Author';
            $author->email = 'author@example.com';
            $authorRepo->save($author);
        }

        // Create Category
        $category = $categoryRepo->findOneBy(['name' => 'Fiction'], false);
        if (!$category) {
            $category = new Category();
            $category->name = 'Fiction';
            $category->description = 'Fictional books and novels';
            $categoryRepo->save($category);
        }

        // Create Books
        for ($i = 1; $i <= 2; $i++) {
            $book = $bookRepo->findOneBy(['title' => "Test Book {$i}"], false);
            if (!$book) {
                $book = new Book();
                $book->title = "Test Book {$i}";
                $book->description = "Description for test book {$i}";
                $book->author = $author;
                $bookRepo->save($book);

                // Create Review for this book
                $review = new Review();
                $review->book = $book;
                $review->user = $user;
                $review->content = "Great book! Review for book {$i}";
                $review->rating = 4 + $i % 2; // Rating 4 or 5
                $reviewRepo->save($review);
            }
        }

        // r_ieate ManyToMany relationship
        $this->ensureManyToManyRelation($authorRepo, $author, 'categories', $category->id);
    }

    private function ensureManyToManyRelation($repo, $entity, string $relation, int $relatedId): void
    {
        $existing = $repo->findByRelation($relation, $relatedId);
        foreach ($existing as $existingEntity) {
            if ($existingEntity->id === $entity->id) {
                return; // Already exists
            }
        }
        $repo->attachRelation($entity, $relation, $relatedId);
    }
}
